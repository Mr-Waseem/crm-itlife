<?php

namespace Tests\Unit;

use App\Models\LeadDetail;
use PHPUnit\Framework\TestCase;

class LeadDetailTest extends TestCase
{
    public function test_lead_statuses_have_the_expected_labels_and_calendar_classes()
    {
        $this->assertSame([
            'New' => ['label' => 'New', 'class' => 'bg-primary'],
            'Contacted' => ['label' => 'Contacted', 'class' => 'bg-info'],
            'No Response' => ['label' => 'No Response', 'class' => 'bg-warning'],
            'Interested' => ['label' => 'Interested', 'class' => 'bg-success'],
            'Converted' => ['label' => 'Converted', 'class' => 'bg-purple'],
            'Lost' => ['label' => 'Lost', 'class' => 'bg-danger'],
        ], LeadDetail::STATUSES);
    }

    public function test_activity_types_track_the_interaction_separately_from_status()
    {
        $this->assertContains('Call', LeadDetail::ACTIVITY_TYPES);
        $this->assertContains('Meeting', LeadDetail::ACTIVITY_TYPES);
        $this->assertContains('WhatsApp', LeadDetail::ACTIVITY_TYPES);
        $this->assertContains('Conversion', LeadDetail::ACTIVITY_TYPES);
        $this->assertNotContains('Interested', LeadDetail::ACTIVITY_TYPES);
    }
}
