import { Box, Container } from '@mui/material'
import styled from '@emotion/styled'
import { colors } from 'consts'

const Body = styled.div`
    background-color: ${colors.primary};
`
const Logo = styled.img`
    max-width: 100%;
    margin-bottom: 3rem;
`

const LoginWrapper = ({ children }: { children: React.ReactNode }) => {
    return (
        <Body>
            <Container component="main" maxWidth="xs" sx={{ height: '100vh' }}>
                <Box
                    sx={{
                        paddingTop: 8,
                        display: 'flex',
                        flexDirection: 'column',
                        alignItems: 'center',
                    }}
                >
                    <Logo src="logo.svg" alt="Komfort" />
                    {children}
                </Box>
            </Container>
        </Body>
    )
}

export default LoginWrapper
