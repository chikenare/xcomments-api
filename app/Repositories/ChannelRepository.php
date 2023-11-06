<?php

namespace App\Repositories;

use App\Interfaces\ChannelI;
use App\Models\Channel;

class ChannelRepository implements ChannelI
{
    public function getChannels()
    {
        return Channel::latest()->paginate(10);
    }
}
