import { List, ListItem, ListItemIcon, ListItemText } from '@mui/material'
import { AssemblyOrder } from 'models/assemblyOrders'
import React from 'react'
import { ReactComponent as Carpet } from 'icons/carpet.svg'
import { ReactComponent as Panel } from 'icons/panel.svg'
import { ReactComponent as Hardwood } from 'icons/hardwood.svg'
import { ReactComponent as Door } from 'icons/door.svg'

interface Props {
    order: AssemblyOrder
}

const Range: React.FC<Props> = ({
    order: { floorCarpetMeters, floorPanelMeters, floorWoodMeters, doorNumber },
}) => {
    return (
        <List disablePadding sx={{ width: '100%' }}>
            {floorCarpetMeters > 0 && (
                <ListItem disableGutters disablePadding divider>
                    <ListItemIcon>
                        <Carpet />
                    </ListItemIcon>
                    <ListItemText>
                        Wykładzina {floorCarpetMeters}m<sup>2</sup>
                    </ListItemText>
                </ListItem>
            )}

            {floorPanelMeters > 0 && (
                <ListItem disableGutters disablePadding divider>
                    <ListItemIcon>
                        <Panel />
                    </ListItemIcon>
                    <ListItemText>
                        Panele {floorPanelMeters}m<sup>2</sup>
                    </ListItemText>
                </ListItem>
            )}

            {floorWoodMeters > 0 && (
                <ListItem disableGutters disablePadding divider>
                    <ListItemIcon>
                        <Hardwood />
                    </ListItemIcon>
                    <ListItemText>
                        Drewno {floorWoodMeters}m<sup>2</sup>
                    </ListItemText>
                </ListItem>
            )}

            {doorNumber > 0 && (
                <ListItem disableGutters disablePadding divider>
                    <ListItemIcon>
                        <Door />
                    </ListItemIcon>
                    <ListItemText>Drzwi {doorNumber}</ListItemText>
                </ListItem>
            )}
        </List>
    )
}

export default Range
