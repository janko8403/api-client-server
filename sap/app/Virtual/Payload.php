<?php

namespace App\Virtual;

/**
 * Action payload.
 *
 * @OA\Schema(
 *     title="Action payload",
 *     required={"idUser", "type", "name", "email", "phoneNumber", "isActive", "idStore", "city", "address", "postalCode", "idMeasurementOrder", "creationDateTime", "installationCity", "installationAddress", "installationName", "idAssembly", "firstDisplayDateTime", "userFirstDisplayDateTime", "userAssignationDateTime", "userDemissionDateTime"},
 * )
 */
class Payload
{
    /**
     * @OA\Property (
     *      title="Id User",
     * )
     *
     * @var int
     */
    public $idUser;
    
    /**
     * @OA\Property (
     *      title="Type",
     * )
     *
     * @var string|null
     */
    public $type;

    /**
     * @OA\Property (
     *      title="Name",
     * )
     *
     * @var string|null
     */
    public $name;

    /**
     * @OA\Property (
     *      title="First name",
     * )
     *
     * @var string|null
     */
    public $firstName;

    /**
     * @OA\Property (
     *      title="Last name",
     * )
     *
     * @var string|null
     */
    public $lastName;
    
    /**
     * @OA\Property (
     *      title="Email",
     * )
     *
     * @var string|null
     */
    public $email;
    
    /**
     * @OA\Property (
     *      title="NPS value",
     * )
     *
     * @var int
     */
    public $npsValue;
    
    /**
     * @OA\Property (
     *      title="Daily productivity",
     * )
     *
     * @var int
     */
    public $dailyProductivity;
    
    /**
     * @OA\Property (
     *      title="List of category",
     * )
     *
     * @var string|null
     */
    public $listOfCategory;
    
    /**
     * @OA\Property (
     *      title="Number phone",
     * )
     *
     * @var string|null
     */
    public $phoneNumber;
    
    /**
     * @OA\Property (
     *      title="Is active",
     * )
     *
     * @var int
     */
    public $isActive;

    /**
     * @OA\Property (
     *      title="Id Shop",
     * )
     *
     * @var string|null
     */
    public $idStore;

    /**
     * @OA\Property (
     *      title="City",
     * )
     *
     * @var string|null
     */
    public $city;

    /**
     * @OA\Property (
     *      title="Address",
     * )
     *
     * @var string|null
     */
    public $address;

    /**
     * @OA\Property (
     *      title="Postal code",
     * )
     *
     * @var string|null
     */
    public $postalCode;

    /**
     * @OA\Property (
     *      title="Id measurement order",
     * )
     *
     * @var int
     */
    public $idMeasurementOrder;

    /**
     * @OA\Property (
     *      title="Id installation order",
     * )
     *
     * @var int
     */
    public $idInstallationOrder;

    /**
     * @OA\Property (
     *      title="Id parent installation order",
     * )
     *
     * @var int
     */
    public $idParentInstallationOrder;

    /**
     * @OA\Property (
     *      title="Measurement status",
     * )
     *
     * @var string|null
     */
    public $measurementStatus;

    /**
     * @OA\Property (
     *      title="Installation status",
     * )
     *
     * @var string|null
     */
    public $installationStatus;

    /**
     * @OA\Property (
     *      title="Assembly creator name",
     * )
     *
     * @var string|null
     */
    public $assemblyCreatorName;
    
    /**
     * @OA\Property (
     *      title="Creation Datetime",
     *      format ="date-time"
     * )
     *
     * @var string|null
     */
    public $creationDateTime;

    /**
     * @OA\Property (
     *      title="Update DateTime",
     * )
     *
     * @var string|null
     */
    public $updateDateTime;

    /**
     * @OA\Property (
     *      title="Delivery DateTime",
     * )
     *
     * @var string|null
     */
    public $deliveryDateTime;
    
    /**
     * @OA\Property (
     *      title="Expected contact DateTime",
     * )
     *
     * @var string|null
     */
    public $expectedContactDateTime;

    /**
     * @OA\Property (
     *      title="Expected installation DateTime",
     * )
     *
     * @var string|null
     */
    public $expectedInstallationDateTime;

    /**
     * @OA\Property (
     *      title="Accepted installation DateTime",
     * )
     *
     * @var string|null
     */
    public $acceptedInstallationDateTime;

    /**
     * @OA\Property (
     *      title="Floor carpet meters",
     * )
     *
     * @var int
     */
    public $floorCarpetMeters;
    
    /**
     * @OA\Property (
     *      title="Floor panel meters",
     * )
     *
     * @var int
     */
    public $floorPanelMeters;
    
    /**
     * @OA\Property (
     *      title="Floor wood meters",
     * )
     *
     * @var int
     */
    public $floorWoodMeters;
    
    /**
     * @OA\Property (
     *      title="Door number",
     * )
     *
     * @var int
     */
    public $doorNumber;
    
    /**
     * @OA\Property (
     *      title="Notification email",
     * )
     *
     * @var string|null
     */
    public $notificationEmail;
    
    /**
     * @OA\Property (
     *      title="Estimated cost net",
     * )
     *
     * @var string|null
     */
    public $estimatedCostNet;
    
    /**
     * @OA\Property (
     *      title="Installation city",
     * )
     *
     * @var string|null
     */
    public $installationCity;
    
    /**
     * @OA\Property (
     *      title="Installation address",
     * )
     *
     * @var string|null
     */
    public $installationAddress;
    
    /**
     * @OA\Property (
     *      title="Installation zip code",
     * )
     *
     * @var string|null
     */
    public $installationZipCode;
    
    /**
     * @OA\Property (
     *      title="Installation name",
     * )
     *
     * @var string|null
     */
    public $installationName;
    
    /**
     * @OA\Property (
     *      title="Installation phone number",
     * )
     *
     * @var string|null
     */
    public $installationPhoneNumber;
    
    /**
     * @OA\Property (
     *      title="Installation email",
     * )
     *
     * @var string|null
     */
    public $installationEmail;
    
    /**
     * @OA\Property (
     *      title="Installation note",
     * )
     *
     * @var string|null
     */
    public $installationNote;
    
    /**
     * @OA\Property (
     *      title="Id user ranking (array)",
     * )
     *
     * @var string|null
     */
    public $idUserRanking;
    
    /**
     * @OA\Property (
     *      title="Ranking position (array)",
     * )
     *
     * @var string|null
     */
    public $rankingPosition;
    
    /**
     * @OA\Property (
     *      title="Id assembly",
     * )
     *
     * @var int
     */
    public $idAssembly;

    /**
     * @OA\Property (
     *      title="First display DateTime",
     * )
     *
     * @var string|null
     */
    public $firstDisplayDateTime;
    
    /**
     * @OA\Property (
     *      title="User first display DateTime",
     * )
     *
     * @var string|null
     */
    public $userFirstDisplayDateTime;
    
    /**
     * @OA\Property (
     *      title="User assignation DateTime",
     * )
     *
     * @var string|null
     */
    public $userAssignationDateTime;
    
    /**
     * @OA\Property (
     *      title="User demission DateTime",
     * )
     *
     * @var string|null
     */
    public $userDemissionDateTime;


}
