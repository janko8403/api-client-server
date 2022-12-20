import {
    Checkbox,
    ListItem,
    ListItemButton,
    ListItemIcon,
    ListItemText,
} from '@mui/material'
import { FilterItem } from 'models/search'
import { SearchContext } from 'providers/SearchProvider'
import React, { useContext } from 'react'

const SearchItem = (item: FilterItem) => {
    const { filters, handleClickItem } = useContext(SearchContext)

    return (
        <ListItem disablePadding divider>
            <ListItemButton
                dense
                disableGutters
                onClick={() => handleClickItem(item.value)}
            >
                <ListItemIcon sx={{ minWidth: 'auto' }}>
                    <Checkbox
                        edge="start"
                        disableRipple
                        checked={filters.includes(item.value)}
                    />
                </ListItemIcon>
                <ListItemText primary={item.label} />
            </ListItemButton>
        </ListItem>
    )
}

export default SearchItem
