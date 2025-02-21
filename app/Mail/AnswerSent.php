<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnswerSent extends Mailable
{
    use Queueable, SerializesModels;

    public $teacher;
    public $student;
    public $course;
    public $class;
    public $activity;

    /**
     * Create a new message instance.
     */
    public function __construct($teacher, $student, $course, $class, $activity)
    {
        $this->teacher = $teacher;
        $this->student = $student;
        $this->course = $course;
        $this->class = $class;
        $this->activity = $activity;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resposta enviada',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.answer-sent',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
