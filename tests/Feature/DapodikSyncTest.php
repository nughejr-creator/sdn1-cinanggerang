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

    public function test_sync_accepts_valid_payload(): void
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
            ]
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

        // Clean up test record
        Student::where('nisn', '9998887771')->delete();
    }
}
