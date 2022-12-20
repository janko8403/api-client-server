import { Box, Chip, Stack, Typography } from '@mui/material'
import dayjs from 'dayjs'
import React from 'react'

interface Props {
    label: string
    date: Date | null
}

const DetailsDate: React.FC<Props> = ({ label, date }) => {
    return (
        <Stack
            direction="row"
            justifyContent="space-between"
            alignItems="center"
        >
            <Typography variant="body2" color="text.secondary">
                {label}
            </Typography>
            <Box gap={1} display="flex">
                {date ? (
                    <>
                        <Chip
                            variant="outlined"
                            label={dayjs(date).format('L')}
                        />
                        <Chip
                            variant="outlined"
                            label={dayjs(date).format('LT')}
                        />
                    </>
                ) : (
                    '-'
                )}
            </Box>
        </Stack>
    )
}

export default DetailsDate
