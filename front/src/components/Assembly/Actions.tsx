import { Box, Button, styled } from '@mui/material'
import { toast } from 'react-toastify'
import { useNavigate, useParams } from 'react-router-dom'
import { useMutation } from 'react-query'
import { ReactComponent as Check } from 'icons/check.svg'
import { ReactComponent as Cancel } from 'icons/cancel.svg'
import { accept, hide } from 'api/assemblyOrders'

const ActionButton = styled(Button)({
    borderRadius: 0,
    flexGrow: 1,
    justifyContent: 'space-between',
    padding: '1rem',
})

const ActionButtonLeft = styled(ActionButton)({
    paddingRight: '2rem',
})

const ActionButtonRight = styled(ActionButton)({
    paddingLeft: '2rem',
})

const Actions = () => {
    const params = useParams()
    const id = +params.id!
    const navigate = useNavigate()
    const { isLoading: isLoadingHide, mutate: mutateHide } = useMutation(hide, {
        onSuccess: () => {
            toast.success('Zlecenie zostało ukryte')
            navigate('/')
        },
    })
    const { isLoading: isLoadingAccept, mutate: mutateAccept } = useMutation(
        accept,
        {
            onSuccess: () => {
                toast.success('Zlecenie zostało przyjęte')
                navigate('/')
            },
        }
    )

    return (
        <Box
            sx={{
                display: 'flex',
                justifyContent: 'space-between',
                gap: '1px',
                position: 'fixed',
                left: 0,
                right: 0,
                bottom: '82px',
            }}
        >
            <ActionButtonLeft
                variant="contained"
                color="error"
                onClick={() => mutateHide(id)}
                disabled={isLoadingHide}
            >
                <Cancel />
                Rezygnuję
            </ActionButtonLeft>
            <ActionButtonRight
                variant="contained"
                color="success"
                onClick={() => mutateAccept(id)}
                disabled={isLoadingAccept}
            >
                Akceptuję
                <Check />
            </ActionButtonRight>
        </Box>
    )
}

export default Actions
