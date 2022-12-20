// @ts-nocheck
import React from 'react'
import { BottomNavigation, BottomNavigationAction, Paper } from '@mui/material'
import { Link, useLocation } from 'react-router-dom'
import styled from '@emotion/styled'
import { ReactComponent as Work } from 'icons/work.svg'
import { ReactComponent as Mine } from 'icons/mine.svg'
import { ReactComponent as Profile } from 'icons/profile.svg'

const NavigationStyled = styled(BottomNavigation)`
    & {
        height: auto;
        gap: 0.5rem;
        padding: 0.5rem;
    }
`

const ActionStyled = styled(BottomNavigationAction)`
    & {
        gap: 0.5rem;
        padding: 0.5rem 0;

        svg {
            color: rgba(0, 0, 0, 0.6);
        }
    }

    &.Mui-selected {
        background-color: #efefef;
        color: #000;
        border-radius: 0.5rem;

        svg {
            color: rgba(0, 0, 0, 1);
        }
    }
`

const BottomNav = () => {
    const location = useLocation()

    return (
        <Paper
            sx={{ position: 'fixed', bottom: 0, left: 0, right: 0 }}
            elevation={3}
        >
            <NavigationStyled showLabels value={location.pathname}>
                <ActionStyled
                    label="Praca"
                    icon={<Work />}
                    component={Link}
                    to="/"
                    value="/"
                />
                <ActionStyled
                    label="Moje zlecenia"
                    icon={<Mine />}
                    component={Link}
                    to="/my-work"
                    value="/my-work"
                />
                <ActionStyled
                    label="Profil"
                    icon={<Profile />}
                    component={Link}
                    to="/profile"
                    value="/profile"
                />
            </NavigationStyled>
        </Paper>
    )
}

export default BottomNav
