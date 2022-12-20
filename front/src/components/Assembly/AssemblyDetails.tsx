import { Box, Divider, Stack, Typography } from '@mui/material'
import { AssemblyOrder } from 'models/assemblyOrders'
import React from 'react'
import Actions from './Actions'
import Address from './Address'
import DetailsDate from './DetailsDate'
import Range from './Range'

interface Props {
    order: AssemblyOrder
}

const AssemblyDetails: React.FC<Props> = ({ order }) => {
    const dates = [
        { label: 'Data utworzenia', date: order.creationDateTime },
        { label: 'Data aktualizacji', date: order.updateDateTime },
        { label: 'Data dostawy', date: order.deliveryDateTime },
        {
            label: 'Data oczekiwanego kontaktu',
            date: order.expectedContactDateTime,
        },
        {
            label: 'Data oczekiwanego montażu',
            date: order.expectedInstallationDateTime,
        },
        {
            label: 'Data uzgodnionego montażu',
            date: order.acceptedInstallationDateTime,
        },
    ]

    return (
        <Box sx={{ pb: 10 }}>
            <Stack spacing={0.5} marginBottom={2}>
                <Typography
                    variant="subtitle2"
                    color="text.secondary"
                    fontWeight="bold"
                >
                    Zakres
                </Typography>
                <Box sx={{ display: 'flex', gap: 1 }}>
                    <Range order={order} />
                </Box>
            </Stack>

            <Address order={order} details />

            <Divider sx={{ mt: 1, mb: 1 }} />

            <Stack direction="row" justifyContent="space-between">
                <Typography variant="body2" color="text.secondary">
                    Sklep
                </Typography>
                <Stack textAlign="right">
                    <Typography variant="body2">
                        {order.customer.name}
                    </Typography>
                    <Typography variant="body2">
                        {order.customer.city} {order.customer.address}
                    </Typography>
                </Stack>
            </Stack>

            <Divider sx={{ mt: 1, mb: 1 }} />

            {dates.map((d) => (
                <React.Fragment key={d.label}>
                    <DetailsDate label={d.label} date={d.date} />
                    <Divider sx={{ mt: 1, mb: 1 }} />
                </React.Fragment>
            ))}

            <Stack
                direction="row"
                justifyContent="space-between"
                marginBottom={2}
            >
                <Typography variant="body2" color="text.secondary">
                    Uwagi
                </Typography>
                <Box>{order.installationNote ?? '-'}</Box>
            </Stack>

            {!order.installationName && <Actions />}
        </Box>
    )
}

export default AssemblyDetails
