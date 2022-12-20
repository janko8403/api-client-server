<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssemblyOrder extends Model
{
    protected $table = 'assemblyOrders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idMeasurementOrder',
        'idInstallationOrder',
        'idParentInstallationOrder',
        'measurementStatus',
        'InstallationStatus',
        'idStore',
        'idUser',
        'assemblyCreatorName',
        'creationDateTime',
        'updateDateTime',
        'deliveryDateTime',
        'expectedContactDateTime',
        'acceptedInstallationDateTime',
        'floorCarpetMeters',
        'floorPanelMeters',
        'floorWoodMeters',
        'doorNumber',
        'notificationEmail',
        'doorNumber',
        'doorNumber',
        'doorNumber',
        'estimatedCostNet',
        'installationCity',
        'installationAddress',
        'installationZipCode',
        'installationName',
        'installationPhoneNumber',
        'installationEmail',
        'installationNote',
        'taken'
    ];

    public $timestamps = false;
}
