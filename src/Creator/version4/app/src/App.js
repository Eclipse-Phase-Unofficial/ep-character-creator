// import 'carbon-components/scss/globals/scss/styles.scss'

import React, { Component } from 'react'
import Layout from './components/layout'

import './App.scss'

import Backgrounds from './components/backgrounds'
import { H } from './components/typography'

const App = () => {
  return (
    <Layout>
      {/* <Loading/> */}
      <H h={1}>Ego</H>
      <H h={2}>Backgrounds</H>
      <Backgrounds />
    </Layout>
  )
}

export default App
