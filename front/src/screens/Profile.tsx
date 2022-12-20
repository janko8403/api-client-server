import { Button, Stack, Typography } from '@mui/material'
import { AuthContext } from 'providers/AuthProvider'
import { useContext } from 'react'
import { useNavigate } from 'react-router-dom'
import RatingsList from 'components/NpsRating/RatingsList'
import { useQuery } from 'react-query'
import { fetchProfile } from 'api/user'

export const Profile = () => {
    const { logout } = useContext(AuthContext)
    const navigate = useNavigate()
    const { data } = useQuery('profile', fetchProfile)

    const handleLogout = () => {
        logout()
        navigate('/')
    }

    return (
        <>
            <Stack
                direction="row"
                justifyContent="space-between"
                alignItems="center"
            >
                <Stack>
                    <Typography variant="h5">Profil</Typography>
                    <Typography
                        variant="body2"
                        sx={{ color: 'text.secondary' }}
                    >
                        {data ? data.email : '-'}
                    </Typography>
                </Stack>
                <Button
                    variant="outlined"
                    color="inherit"
                    onClick={handleLogout}
                >
                    Wyloguj się
                </Button>
            </Stack>
            <RatingsList />
        </>
    )
}

export default Profile
