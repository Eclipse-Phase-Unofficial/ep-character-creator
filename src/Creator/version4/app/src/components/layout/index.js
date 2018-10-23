import React from 'react'
import styled from 'styled-components'

import Header from '../header/index'
import SideNav from '../side-nav/index'

const LayoutContainer = styled.div`
  margin: 48px;
  display: flex;
  justify-content: center;
`

const LayoutContent = styled.div`
  display: grid;
  grid-gap: 10px;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
  grid-area: auto;
  width: 1000px;
`

export const Layout = ({ children }) => (
  <React.Fragment>
    <Header />
    <SideNav />
    <LayoutContainer>
      <LayoutContent>
        {children}
      </LayoutContent>
    </LayoutContainer>
  </React.Fragment>
)

export default Layout
