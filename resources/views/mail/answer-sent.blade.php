<x-mail::message>
# Prezado {{ $teacher }}

Resposta enviada pelo aluno {{ $student }}.
    
## Curso: {{ $course}}
## Turma: {{ $class }}
## Atividade: {{ $activity }}

<x-mail::button :url="'https://ead.atitudeidiomas.com/admin/resposta-enviada'">
Ver resposta
</x-mail::button>

Atenciosamente,<br>
Equipe {{ config('app.name') }}
</x-mail::message>
