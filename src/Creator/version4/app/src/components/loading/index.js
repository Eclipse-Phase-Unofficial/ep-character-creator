import React from 'react'
import styled from 'styled-components'

import logo from './logo.svg'

const AppWrapper = styled.div`
  text-align: center;
`

const AppHeader = styled.header`
  background-color: #282c34;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  font-size: calc(10px + 2vmin);
  color: white;
`

const AppLogo = styled.img`
  animation: App-logo-spin infinite 20s linear;
  height: 40vmin;

  @keyframes App-logo-spin {
    from {
      transform: rotate(0deg);
    }
    to {
      transform: rotate(360deg);
    }
  }  
`

export const Loading = () => (<AppWrapper>
  <AppHeader>
    <AppLogo src={logo} alt='logo' />
    <h1 className='App-title'>Welcome to React, with Carbon!</h1>
  </AppHeader>
  <p>
    To get started, edit <code>src/App.js</code> and save to reload.
  </p>
</AppWrapper>)

export default Loading
