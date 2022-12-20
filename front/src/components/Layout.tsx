import { Outlet, useLocation, useNavigate } from 'react-router-dom'
import { AppBar, Box, Container, Toolbar } from '@mui/material'
import BottomNav from 'components/BottomNav'
import BackButton from './BackButton'
import { colors } from 'consts'
import Search from './Search'

const Layout = () => {
    const navigate = useNavigate()
    const location = useLocation()
    const showBack =
        location.pathname.match(/^\/assembly\/\d+$/) ||
        location.pathname.match(/^\/search$/)
    const detailsPage = location.pathname.match(/^\/assembly\/\d+$/)
    const showSearch = location.pathname.match(/^\/$/)

    const containerStyle = detailsPage
        ? {
              paddingTop: 1,
              paddingBottom: 10,
              borderRadius: 3,
              marginTop: -3,
              backgroundColor: '#fff',
          }
        : { paddingTop: 1 }

    return (
        <Box
            sx={{
                backgroundImage: detailsPage ? 'url(/floor.png)' : '',
                backgroundSize: 'contain',
                backgroundRepeat: 'no-repeat',
                backgroundPositionX: 'center',
            }}
        >
            <AppBar
                position="static"
                elevation={0}
                sx={{
                    mb: 2,
                    backgroundColor: detailsPage
                        ? 'transparent'
                        : colors.primary,
                }}
            >
                <Container maxWidth="lg" sx={{ mb: detailsPage ? '300px' : 0 }}>
                    <Toolbar
                        sx={{
                            justifyContent: 'center',
                            position: 'relative',
                            boxShadow: 'none',
                        }}
                    >
                        {showBack && (
                            <BackButton onClick={() => navigate(-1)} />
                        )}
                        <img
                            src="/logo.svg"
                            alt="Komfort"
                            style={{
                                maxHeight: '2.5rem',
                            }}
                        />
                        {showSearch && <Search />}
                    </Toolbar>
                </Container>
            </AppBar>

            <Container maxWidth="lg" sx={containerStyle}>
                <Outlet />
            </Container>

            <BottomNav />
        </Box>
    )
}

export default Layout
