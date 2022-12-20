import { Box, Card, CardContent, Chip, Stack, Typography } from '@mui/material'
import { AssemblyOrder } from 'models/assemblyOrders'
import { ReactComponent as Pin } from 'icons/pin.svg'
import React from 'react'

interface Props {
    order: AssemblyOrder
    details?: boolean
}

const Address: React.FC<Props> = ({
    details,
    order: {
        installationCity,
        installationAddress,
        installationZipCode,
        installationName,
        installationPhoneNumber,
        installationEmail,
    },
}) => {
    return (
        <Card variant="outlined" sx={{ mt: 2, mb: 2 }}>
            <CardContent sx={{ pb: '1rem !important' }}>
                <Stack direction="row" justifyContent="space-between">
                    <Typography
                        variant="subtitle2"
                        color="text.secondary"
                        fontWeight="bold"
                    >
                        <Pin /> Montaż
                    </Typography>
                    <Box textAlign="right">
                        <Box>{installationAddress}</Box>
                        <Box marginBottom={1}>
                            {installationZipCode} {installationCity}
                        </Box>
                        {installationName && details && (
                            <Stack spacing={1}>
                                <Chip
                                    variant="outlined"
                                    color="error"
                                    label={installationName}
                                />
                                <Chip
                                    variant="outlined"
                                    color="error"
                                    label={installationPhoneNumber}
                                    component="a"
                                    href={`tel:${installationPhoneNumber}`}
                                />
                                <Chip
                                    variant="outlined"
                                    color="error"
                                    label={installationEmail}
                                    component="a"
                                    href={`mailto:${installationEmail}`}
                                />
                            </Stack>
                        )}
                    </Box>
                </Stack>
            </CardContent>
        </Card>
    )
}

export default Address
