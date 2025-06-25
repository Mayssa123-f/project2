<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Eager load roles to avoid N+1 query problem
        return User::with('roles')->get();
    }

    // Define the column headers
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Password',
            'Created At',
            'Updated At',
            'Roles',
        ];
    }

    // Map each user row to the columns for export
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->password, // careful with exporting password hashes!
            $user->created_at,
            $user->updated_at,
            $user->roles->pluck('name')->implode(', '),  // comma-separated roles
        ];
    }
}
