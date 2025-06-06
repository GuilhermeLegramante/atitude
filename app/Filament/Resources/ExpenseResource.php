<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Filament\Resources\ExpenseResource\Widgets\ExpenseStats;
use App\Models\Expense;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Financeiro';

    protected static ?string $modelLabel = 'despesa';

    protected static ?string $pluralModelLabel = 'despesas';

    protected static ?string $slug = 'despesa';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Dados da Despesa')
                ->schema([
                    Forms\Components\Select::make('expense_category_id')
                        ->label('Categoria')
                        ->relationship('category', 'name')
                        ->required(),

                    Forms\Components\TextInput::make('description')
                        ->label('Descrição')
                        ->required(),

                    Forms\Components\TextInput::make('amount')
                        ->label('Valor')
                        ->numeric()
                        ->required(),

                    Forms\Components\DatePicker::make('due_date')
                        ->label('Data de vencimento')
                        ->required(),

                    Forms\Components\DatePicker::make('payment_date')
                        ->label('Data de pagamento'),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'aberto' => 'Aberto',
                            'pago' => 'Pago',
                            'vencido' => 'Vencido',
                            'cancelado' => 'Cancelado',
                        ])
                        ->default('aberto'),

                    Forms\Components\FileUpload::make('attachments')
                        ->label('Anexos')
                        ->multiple()
                        ->directory('expenses/attachments'),
                ])
                ->columns(2),
            Forms\Components\Section::make('Recorrência')
                ->schema([
                    Forms\Components\Toggle::make('is_recurring')
                        ->label('Despesa Recorrente')
                        ->reactive(),

                    Forms\Components\Select::make('recurrence_type')
                        ->label('Tipo de recorrência')
                        ->options([
                            'mensal' => 'Mensal',
                            'anual' => 'Anual',
                            'semanal' => 'Semanal',
                        ])
                        ->visible(fn($get) => $get('is_recurring'))
                        ->required(fn($get) => $get('is_recurring')),

                    Forms\Components\DatePicker::make('recurrence_end_date')
                        ->label('Repetir até')
                        ->visible(fn($get) => $get('is_recurring')),
                ])
                ->columns(2), // Define layout em duas colunas
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('category.name')->label('Categoria')->sortable(),
            Tables\Columns\TextColumn::make('description')->label('Descrição')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('amount')->label('Valor')->money('BRL')->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'aberto' => 'warning',
                    'pago' => 'success',
                    'vencido' => 'danger',
                    'cancelado' => 'gray',
                    default => 'secondary',
                }),
            Tables\Columns\TextColumn::make('due_date')->label('Data de vencimento')->date('d/m/Y'),
            Tables\Columns\TextColumn::make('payment_date')->label('Data de pagamento')->date('d/m/Y'),
            Tables\Columns\TextColumn::make('is_recurring')
                ->label('Recorrente')
                ->formatStateUsing(fn($state) => $state ? 'Sim' : 'Não'),
            Tables\Columns\TextColumn::make('attachments')->label('Anexos')->getStateUsing(fn($record) => count($record->attachments ?? []) . ' arquivo(s)'),
        ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aberto' => 'Aberto',
                        'pago' => 'Pago',
                        'vencido' => 'Vencido',
                        'cancelado' => 'Cancelado',
                    ]),
                Filter::make('due_date_month')
                    ->label('Mês')
                    ->form([
                        Forms\Components\Select::make('month')
                            ->label('Mês')
                            ->options([
                                1 => 'Janeiro',
                                2 => 'Fevereiro',
                                3 => 'Março',
                                4 => 'Abril',
                                5 => 'Maio',
                                6 => 'Junho',
                                7 => 'Julho',
                                8 => 'Agosto',
                                9 => 'Setembro',
                                10 => 'Outubro',
                                11 => 'Novembro',
                                12 => 'Dezembro',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('year')
                            ->label('Ano')
                            ->default(date('Y'))
                            ->numeric()
                            ->required(),
                    ])
                    ->query(function ($query, array $data) {
                        // Se não selecionar mês ou ano, não filtra nada (traz tudo)
                        if (empty($data['month']) || empty($data['year'])) {
                            return $query;
                        }

                        return $query
                            ->whereYear('due_date', $data['year'])
                            ->whereMonth('due_date', $data['month']);
                    }),
            ])
            ->deferFilters()
            ->filtersApplyAction(
                fn(Action $action) => $action
                    ->link()
                    ->label('Aplicar Filtro(s)'),
            )
            ->actions([
                Action::make('markAsPaid')
                    ->label('Marcar como Pago')
                    ->icon('heroicon-m-check-circle')
                    ->color('info')
                    ->visible(fn($record) => $record->status !== 'pago')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'pago',
                            'payment_date' => now(),
                        ]);
                    }),
                Tables\Actions\EditAction::make(),
                Action::make('gerarRecorrencia')
                    ->label('Gerar Próxima Recorrência')
                    ->icon('heroicon-m-plus')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->is_recurring)
                    ->action(function ($record) {
                        $nextDueDate = match ($record->recurrence_type) {
                            'semanal' => Carbon::parse($record->due_date)->addWeek(),
                            'mensal' => Carbon::parse($record->due_date)->addMonth(),
                            'anual' => Carbon::parse($record->due_date)->addYear(),
                            default => null,
                        };

                        if ($nextDueDate && $record->recurrence_end_date && $nextDueDate > $record->recurrence_end_date) {
                            Notification::make()
                                ->title('Aviso')
                                ->body('Recorrência encerrada.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $record->replicate()->fill([
                            'due_date' => $nextDueDate,
                            'payment_date' => null,
                            'status' => 'aberto',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ])->save();

                        Notification::make()
                            ->title('Aviso')
                            ->body('Próxima recorrência gerada com sucesso!')
                            ->success()
                            ->send();
                    }),
            ])->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ExpenseStats::class,
        ];
    }
}
