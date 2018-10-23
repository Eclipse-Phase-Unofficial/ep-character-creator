import React from 'react'
import styled from 'styled-components'

export const GridCell = styled.div`
  grid-column: span ${({ columns }) => columns.xs};
`
export const withColumn = (columns = { xs: 12 }) => Component => props => <GridCell columns={columns}><Component {...props} /></GridCell>
