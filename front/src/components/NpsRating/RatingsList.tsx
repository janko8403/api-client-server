import { Box, Button, List, ListItem, Typography } from '@mui/material'
import { fetchNpsRatings } from 'api/npsRatings'
import Item from 'components/NpsRating/Item'
import Spinner from 'components/Spinner'
import React from 'react'
import { useInfiniteQuery } from 'react-query'

const RatingsList = () => {
    const fetchData = ({ pageParam = 1 }) => fetchNpsRatings(pageParam)
    const { data, fetchNextPage, hasNextPage, isFetchingNextPage, status } =
        useInfiniteQuery('npsRatings', fetchData, {
            getNextPageParam: (lastPage) => lastPage.nextPage,
        })

    if (status === 'loading') return <Spinner />

    return (
        <Box paddingBottom={15}>
            <List>
                {data?.pages.map((p, i) => (
                    <React.Fragment key={i}>
                        {p.data.map((r) => (
                            <ListItem key={r.id} disableGutters>
                                <Item rating={r} />
                            </ListItem>
                        ))}
                    </React.Fragment>
                ))}
            </List>

            {hasNextPage ? (
                <Box textAlign="center">
                    <Button
                        onClick={() => fetchNextPage()}
                        disabled={isFetchingNextPage}
                    >
                        Pokaż więcej
                    </Button>
                </Box>
            ) : (
                <Box textAlign="center">
                    <Typography
                        variant="overline"
                        color={(theme) => theme.palette.success.main}
                    >
                        Wszystkie ankiety zostały wczytane
                    </Typography>
                </Box>
            )}
        </Box>
    )
}

export default RatingsList
