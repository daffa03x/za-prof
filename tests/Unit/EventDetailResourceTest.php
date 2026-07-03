<?php

namespace Tests\Unit;

use App\Http\Resources\EventDetailResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Tests\TestCase;

class EventDetailResourceTest extends TestCase
{
    public function test_event_detail_resource_includes_benefits(): void
    {
        $event = new Event([
            'name' => 'Sostrip Test',
            'slug' => 'sostrip-test',
            'harga' => 95000,
            'benefits' => [
                'Goodie bag peserta',
                'Dokumentasi kegiatan',
            ],
            'agenda' => [
                [
                    'time_label' => 'Pagi',
                    'title' => 'Registrasi ulang',
                    'description' => 'Peserta melakukan check-in di lokasi.',
                ],
            ],
            'jumlah_tiket' => 25,
            'status' => true,
        ]);
        $event->id = 1;
        $event->direction = 'https://maps.google.com/?q=Tahura+Djuanda';

        $payload = (new EventDetailResource($event))->toArray(Request::create('/api/events/sostrip-test'));

        $this->assertSame([
            'Goodie bag peserta',
            'Dokumentasi kegiatan',
        ], $payload['benefits']);
        $this->assertSame([
            [
                'time_label' => 'Pagi',
                'title' => 'Registrasi ulang',
                'description' => 'Peserta melakukan check-in di lokasi.',
            ],
        ], $payload['agenda']);
        $this->assertSame('https://maps.google.com/?q=Tahura+Djuanda', $payload['direction_url']);
    }
}
