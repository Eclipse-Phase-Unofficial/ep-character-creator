import React, { Fragment } from 'react'
import styled from 'styled-components'
import { Tile } from 'carbon-components-react'

const StyledTile = styled(Tile)`
  padding: 0;
`

const HeaderWrapper = styled.div`
  background-color: ${({theme}) => theme.colors.navyGrey[90]};
  padding: ${({theme}) => theme.sizes.layout['2xs']};
  
  border-bottom: 1px solid black;
  border-bottom-color: ${({theme}) => theme.colors.blue[51]};
`

const ContentWrapper = styled.div`
  padding: ${({theme}) => theme.sizes.layout['2xs']};
`

export const Card = ({title, children}) => <StyledTile>
  <HeaderWrapper>
    <h2>{title}</h2>
  </HeaderWrapper>
  <ContentWrapper>
    {children}
  </ContentWrapper>
</StyledTile>
