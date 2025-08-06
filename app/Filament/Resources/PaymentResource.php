<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Financeiro';

    protected static ?string $modelLabel = 'pagamento';

    protected static ?string $pluralModelLabel = 'pagamentos';

    protected static ?string $slug = 'pagamento';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Dados do Pagamento')
                ->schema([
                    Forms\Components\Select::make('student_id')
                        ->label('Aluno')
                        ->relationship('student', 'name')
                        ->searchable()
                        ->required(),

                    Forms\Components\TextInput::make('description')
                        ->label('Descrição')
                        ->required(),

                    Forms\Components\TextInput::make('amount')
                        ->label('Valor')
                        ->numeric()
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'aberto' => 'Aberto',
                            'pago' => 'Pago',
                            'vencido' => 'Vencido',
                            'cancelado' => 'Cancelado',
                        ])
                        ->default('aberto'),

                    Forms\Components\DatePicker::make('due_date')
                        ->label('Data de vencimento')
                        ->required(),

                    Forms\Components\DatePicker::make('payment_date')
                        ->label('Data de pagamento'),

                    Forms\Components\TextInput::make('payment_method')
                        ->label('Forma de pagamento')
                        ->placeholder('Pix, boleto, dinheiro...'),
                ])
                ->columns(2),
            Forms\Components\Section::make('Recorrência')
                ->schema([
                    Forms\Components\Toggle::make('is_recurring')
                        ->label('Pagamento Recorrente')
                        ->reactive(),

                    Forms\Components\Select::make('recurrence_type')
                        ->label('Tipo de recorrência')
                        ->options([
                            'mensal' => 'Mensal',
                            'anual' => 'Anual',
                            'semestral' => 'Semestral',
                            'trimestral' => 'Trimestral',
                            'diario' => 'Diário',
                        ])
                        ->visible(fn($get) => $get('is_recurring'))
                        ->required(fn($get) => $get('is_recurring')),

                    Forms\Components\DatePicker::make('recurrence_end_date')
                        ->label('Repetir até')
                        ->visible(fn($get) => $get('is_recurring')),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')->label('Aluno')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('description')->label('Descrição'),
                Tables\Columns\TextColumn::make('amount')->label('Valor')->money('BRL'),
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

            ])
            ->deferFilters()
            ->filtersApplyAction(
                fn(Action $action) => $action
                    ->link()
                    ->label('Aplicar Filtro(s)'),
            )
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
                Action::make('recibo')
                    ->label('Gerar Recibo')
                    ->icon('heroicon-o-document-text')
                    ->url(fn(Payment $record) => route('payments.receipt', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
