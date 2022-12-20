import { Card, CardContent, CardMedia, Stack, Typography } from '@mui/material'
import dayjs from 'dayjs'
import { AssemblyOrder } from 'models/assemblyOrders'
import React from 'react'
import { Link } from 'react-router-dom'
import Address from './Address'
import Range from './Range'
import Tags from './Tags'

interface Props {
    order: AssemblyOrder
}

const AssemblyListItem: React.FC<Props> = ({ order }) => {
    return (
        <Link
            to={`/assembly/${order.id}`}
            inlist="true"
            style={{ textDecoration: 'none' }}
        >
            <Card sx={{ mb: 3, borderRadius: '0.6rem' }} raised>
                <CardMedia component="img" height="120" image="/floor.png" />
                <CardContent sx={{ position: 'relative' }}>
                    <Tags order={order} />

                    <Stack
                        direction="row"
                        justifyContent="space-between"
                        alignItems="center"
                    >
                        <Typography
                            variant="subtitle2"
                            color="text.secondary"
                            fontWeight="bold"
                        >
                            {order.expectedInstallationDateTime
                                ? 'Data montażu'
                                : 'Data utworzenia'}
                        </Typography>
                        <Typography variant="subtitle1" color="text.secondary">
                            {order.expectedInstallationDateTime
                                ? dayjs(
                                      order.expectedInstallationDateTime
                                  ).format('L')
                                : dayjs(order.creationDateTime).format('L')}
                        </Typography>
                    </Stack>

                    <Range order={order} />

                    <Address order={order} />
                </CardContent>
            </Card>
        </Link>
    )
}

export default AssemblyListItem
