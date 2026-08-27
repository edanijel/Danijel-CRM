<?php

namespace App\Enums;

enum DealStatus: string
{
    case New = 'new';
    case Qualification = 'qualification';
    case ProposalSent = 'proposal_sent';
    case Negotiation = 'negotiation';
    case Won = 'won';
    case Lost = 'lost';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Qualification => 'Qualification',
            self::ProposalSent => 'Proposal Sent',
            self::Negotiation => 'Negotiation',
            self::Won => 'Won',
            self::Lost => 'Lost',
        };
    }
}
