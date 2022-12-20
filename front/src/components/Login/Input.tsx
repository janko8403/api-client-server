import styled from '@emotion/styled'
import { TextField } from '@mui/material'

const Input = styled(TextField)`
    & {
        .MuiInputBase-root {
            background-color: rgba(255, 255, 255, 0.9);

            outline: 0;
            :focus-visible {
            }
        }

        .MuiOutlinedInput-notchedOutline {
            border-color: #fff;
        }
    }
`

export default Input
