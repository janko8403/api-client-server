import {
    Box,
    Dialog,
    DialogContent,
    Slide,
    useMediaQuery,
    useTheme,
    List,
    Typography,
    AppBar,
    Container,
    Toolbar,
    ListSubheader,
} from '@mui/material'
import { TransitionProps } from '@mui/material/transitions'
import React, { useContext } from 'react'
import FilterListIcon from '@mui/icons-material/FilterList'
import ButtonWhite from './ButtonWhite'
import { colors } from 'consts'
import BackButton from './BackButton'
import { FilterItem } from 'models/search'
import SearchItem from './SearchItem'
import { SearchContext } from 'providers/SearchProvider'

const filtersCategory: FilterItem[] = [
    { label: 'Deska klejona', value: 'hardwood' },
    { label: 'Panele', value: 'panels' },
    { label: 'Wykładziny', value: 'carpet' },
    { label: 'Drzwi', value: 'door' },
]

const filtersTime: FilterItem[] = [
    { label: '7 dni', value: '7' },
    { label: '8 - 14 dni', value: '8-14' },
    { label: '14 - 28 dni', value: '14-28' },
    { label: '> 28 dni', value: '28' },
]

const Transition = React.forwardRef(function Transition(
    props: TransitionProps & {
        children: React.ReactElement<any, any>
    },
    ref: React.Ref<unknown>
) {
    return <Slide direction="left" ref={ref} {...props} />
})

const Search = () => {
    const theme = useTheme()
    const fullScreen = useMediaQuery(theme.breakpoints.down('md'))
    const { open, handleOpen, handleClose } = useContext(SearchContext)

    return (
        <>
            <Box
                sx={{
                    display: 'flex',
                    alignItems: 'center',
                    alignContent: 'center',
                    position: 'absolute',
                    right: 0,
                    cursor: 'pointer',
                }}
            >
                <a onClick={handleOpen}>
                    <FilterListIcon />
                </a>
            </Box>
            <Dialog
                open={open}
                TransitionComponent={Transition}
                keepMounted
                onClose={handleClose}
                fullScreen={fullScreen}
                fullWidth
                maxWidth="md"
            >
                <AppBar
                    position="static"
                    elevation={0}
                    sx={{ mb: 2, backgroundColor: colors.primary }}
                >
                    <Container maxWidth="lg">
                        <Toolbar
                            sx={{
                                justifyContent: 'center',
                                position: 'relative',
                                boxShadow: 'none',
                            }}
                        >
                            <BackButton onClick={handleClose} />
                            <img
                                src="/logo.svg"
                                alt="Komfort"
                                style={{ maxHeight: '3rem' }}
                            />
                        </Toolbar>
                    </Container>
                </AppBar>
                <DialogContent>
                    <Typography variant="h5">Filtry</Typography>

                    <List
                        subheader={
                            <ListSubheader disableGutters>
                                Kategoria
                            </ListSubheader>
                        }
                    >
                        {filtersCategory.map((f) => (
                            <SearchItem
                                label={f.label}
                                value={f.value}
                                key={f.value}
                            />
                        ))}
                    </List>

                    <List
                        subheader={
                            <ListSubheader disableGutters>
                                Czas realizacji
                            </ListSubheader>
                        }
                    >
                        {filtersTime.map((f) => (
                            <SearchItem
                                label={f.label}
                                value={f.value}
                                key={f.value}
                            />
                        ))}
                    </List>

                    <Box sx={{ display: 'flex', justifyContent: 'center' }}>
                        <ButtonWhite
                            label="Filtruj"
                            type="button"
                            onClick={handleClose}
                        />
                    </Box>
                </DialogContent>
            </Dialog>
        </>
    )
}

export default Search
