export interface Customer {
    name: string
    address: string
    zipCode: string
    city: string | null
}

export interface AssemblyOrder {
    id: number
    idMeasurementOrder: number
    idInstallationOrder: number
    idParentInstallationOrder: number
    measurementStatus: string
    installationStatus: string
    idStore: string
    idUser: number
    assemblyCreatorName: string
    creationDateTime: Date | null
    updateDateTime: Date | null
    deliveryDateTime: Date | null
    expectedContactDateTime: Date | null
    expectedInstallationDateTime: Date | null
    acceptedInstallationDateTime: Date | null
    floorCarpetMeters: number
    floorPanelMeters: number
    floorWoodMeters: number
    doorNumber: number
    notificationEmail: string
    estimatedCostNet: number
    installationCity: string
    installationAddress: string
    installationZipCode: string
    installationName: string
    installationPhoneNumber: string
    installationEmail: string
    installationNote: string
    customer: Customer
}

export enum AssemblyOrderStatus {
    Available = 'available',
    Accepted = 'accepted',
}
