<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\StudentClass;
use Tests\TestCase;

class DapodikSyncTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'test',
        ]);
    }

    public function test_sync_rejects_unauthorized_token(): void
    {
        $response = $this->postJson('/api/dapodik/sync-students', [
            'students' => [],
        ]);

        $response->assertStatus(401);
    }

    public function test_sync_accepts_valid_payload_and_updates_class(): void
    {
        $payload = [
            'students' => [
                [
                    'nama' => 'Budi Santoso Uji Coba',
                    'nisn' => '9998887771',
                    'nik' => '3211130000000001',
                    'jenis_kelamin' => 'L',
                    'tempat_lahir' => 'Sumedang',
                    'tanggal_lahir' => '2016-05-10',
                    'nama_rombel' => 'Kelas IV',
                    'agama_id_str' => 'Islam',
                    'alamat_jalan' => 'Dusun Cinanggerang',
                ]
            ],
            'rombels' => [
                [
                    'nama' => 'Kelas IV',
                    'tingkat_pendidikan_id' => 4,
                ]
            ],
            'prune_missing' => false,
        ];

        $response = $this->withHeaders([
            'X-Dapodik-Token' => 'Cu7bJNdcgqTI42V',
        ])->postJson('/api/dapodik/sync-students', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('students', [
            'nisn' => '9998887771',
            'nama' => 'Budi Santoso Uji Coba',
        ]);

        // Uji coba kenaikan kelas (pindah ke Kelas V)
        $payloadNaikKelas = [
            'students' => [
                [
                    'nama' => 'Budi Santoso Uji Coba',
                    'nisn' => '9998887771',
                    'nik' => '3211130000000001',
                    'jenis_kelamin' => 'L',
                    'tempat_lahir' => 'Sumedang',
                    'tanggal_lahir' => '2016-05-10',
                    'nama_rombel' => 'Kelas V',
                    'agama_id_str' => 'Islam',
                    'alamat_jalan' => 'Dusun Cinanggerang',
                ]
            ],
            'rombels' => [
                [
                    'nama' => 'Kelas V',
                    'tingkat_pendidikan_id' => 5,
                ]
            ],
            'prune_missing' => false,
        ];

        $response2 = $this->withHeaders([
            'X-Dapodik-Token' => 'Cu7bJNdcgqTI42V',
        ])->postJson('/api/dapodik/sync-students', $payloadNaikKelas);

        $response2->assertStatus(200);
        $this->assertEquals(1, $response2->json('data.class_changed'));

        // Clean up test record
        Student::where('nisn', '9998887771')->delete();
    }
}
