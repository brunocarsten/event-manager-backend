<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Allocation;

class Person extends Model
{
    use HasFactory;
    protected $table = 'people';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['first_name', 'last_name'];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'first_name' => 'string',
        'last_name' => 'string',
    ];

    public function allocations()
    {
        return $this->hasMany(Allocation::class);
    }
}
