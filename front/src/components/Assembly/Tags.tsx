import { Chip, Stack } from '@mui/material'
import { AssemblyOrder } from 'models/assemblyOrders'
import React from 'react'
import { ReactComponent as Carpet } from 'icons/carpet.svg'
import { ReactComponent as Panel } from 'icons/panel.svg'
import { ReactComponent as Hardwood } from 'icons/hardwood.svg'
import { ReactComponent as Door } from 'icons/door.svg'

interface Props {
    order: AssemblyOrder
}

const Tags: React.FC<Props> = ({
    order: { floorCarpetMeters, floorPanelMeters, floorWoodMeters, doorNumber },
}) => {
    return (
        <Stack
            direction="row"
            spacing={1}
            justifyContent="end"
            position="absolute"
            top="-40px"
            left="1rem"
            right="1rem"
        >
            {floorCarpetMeters > 0 && (
                <Chip
                    label="Wykładzina"
                    avatar={<Carpet />}
                    sx={{ backgroundColor: '#fff' }}
                />
            )}
            {floorPanelMeters > 0 && (
                <Chip
                    label="Panele"
                    avatar={<Panel />}
                    sx={{ backgroundColor: '#fff' }}
                />
            )}

            {floorWoodMeters > 0 && (
                <Chip
                    label="Drewno"
                    avatar={<Hardwood />}
                    sx={{ backgroundColor: '#fff' }}
                />
            )}

            {doorNumber > 0 && (
                <Chip
                    label="Drzwi"
                    avatar={<Door />}
                    sx={{ backgroundColor: '#fff' }}
                />
            )}
        </Stack>
    )
}

export default Tags
