<?php

namespace App\Models\Discord;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bots extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'discord_bots';

    protected $fillable = [
        'name', 'desc', 'client_id',
    ];

    public function getInviteURL()
    {
        $invite_url = "https://discord.com/api/oauth2/authorize?client_id=$this->client_id";

        if (!is_null($this->permissions)) {
            $invite_url .= "&permissions=$this->permissions";
        }

        $invite_url .= "&scope=$this->scope";

        return $invite_url;
    }
}
