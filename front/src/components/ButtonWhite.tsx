import { Button } from '@mui/material'
import React from 'react'
import styled from '@emotion/styled'

interface Props {
    label: string
    type: 'submit' | 'button'
    disabled?: boolean
    onClick?: () => void
}

const ButtonStyled = styled(Button)`
    & {
        background-color: #fff;
        color: #000;

        :hover {
            background-color: #fff;
        }
    }
`

const ButtonWhite: React.FC<Props> = ({ label, type, onClick, disabled }) => {
    return (
        <ButtonStyled
            type={type}
            variant="contained"
            size="large"
            sx={{ mt: 3 }}
            onClick={onClick}
            disabled={disabled}
        >
            {label}
        </ButtonStyled>
    )
}

export default ButtonWhite
