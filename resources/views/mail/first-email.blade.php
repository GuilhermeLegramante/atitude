<x-mail::message>
# Introduction

{{ $messageText }}

<x-mail::button :url="''">
    Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
