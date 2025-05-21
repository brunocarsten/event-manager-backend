<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Allocation extends Model
{
    use HasFactory;
    protected $table = 'allocations';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['person_id', 'room_id', 'coffee_space_id', 'stage'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'person_id' => 'integer',
        'room_id' => 'integer',
        'coffee_space_id' => 'integer',
        'stage' => 'integer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function coffeeSpace()
    {
        return $this->belongsTo(CoffeeSpace::class);
    }
}
