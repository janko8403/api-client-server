import {
    Card,
    CardContent,
    List,
    ListItem,
    Stack,
    Typography,
} from '@mui/material'
import dayjs from 'dayjs'
import { NpsRating } from 'models/npsRatings'
import React from 'react'

interface Props {
    rating: NpsRating
}

const Item: React.FC<Props> = ({ rating }) => {
    return (
        <Card>
            <CardContent>
                <List>
                    <ListItem disableGutters divider>
                        <Stack
                            direction="row"
                            justifyContent="space-between"
                            width="100%"
                        >
                            <Typography variant="body2" color="text.secondary">
                                Data wypełnienia ankiety
                            </Typography>
                            <Typography variant="body2">
                                {dayjs(rating.date).format('L')}
                            </Typography>
                        </Stack>
                    </ListItem>
                    <ListItem disableGutters divider>
                        <Stack
                            direction="row"
                            justifyContent="space-between"
                            width="100%"
                        >
                            <Typography variant="body2" color="text.secondary">
                                Email montażysty
                            </Typography>
                            <Typography variant="body2" color="error">
                                {rating.email}
                            </Typography>
                        </Stack>
                    </ListItem>
                    <ListItem disableGutters divider>
                        <Stack
                            direction="row"
                            justifyContent="space-between"
                            width="100%"
                        >
                            <Typography variant="body2" color="text.secondary">
                                Nazwa klienta
                            </Typography>
                            <Typography variant="body2">
                                {rating.customerName}
                            </Typography>
                        </Stack>
                    </ListItem>
                    <ListItem disableGutters divider>
                        <Stack
                            direction="row"
                            justifyContent="space-between"
                            width="100%"
                        >
                            <Typography variant="body2" color="text.secondary">
                                Ocena NPS
                            </Typography>
                            <Typography variant="body2">
                                {rating.rating}
                            </Typography>
                        </Stack>
                    </ListItem>
                </List>

                <Typography
                    variant="body2"
                    color="text.secondary"
                    marginBottom={1}
                >
                    Komentarz
                </Typography>
                <Typography variant="body2">{rating.comment}</Typography>
            </CardContent>
        </Card>
    )
}

export default Item
