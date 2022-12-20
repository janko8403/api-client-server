import { Box, IconButton } from '@mui/material'
import React from 'react'
import ChevronLeftIcon from '@mui/icons-material/ChevronLeft'

interface Props {
    onClick: () => void
}

const BackButton: React.FC<Props> = ({ onClick }) => {
    return (
        <Box
            sx={{
                display: 'flex',
                alignItems: 'center',
                alignContent: 'center',
                position: 'absolute',
                left: 0,
                cursor: 'pointer',
            }}
        >
            <IconButton
                onClick={onClick}
                sx={{
                    display: 'flex',
                    alignItems: 'center',
                    backgroundColor: '#fff',
                }}
            >
                <ChevronLeftIcon sx={{ color: '#000' }} />
            </IconButton>
        </Box>
    )
}

export default BackButton
