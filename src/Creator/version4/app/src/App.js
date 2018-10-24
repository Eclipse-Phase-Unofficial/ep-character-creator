// import 'carbon-components/scss/globals/scss/styles.scss'

import React from 'react'
import Layout from './components/layout'

import './App.scss'

import Backgrounds from './components/backgrounds'
import { H } from './components/typography'
import GearList from './components/gear'
import AiList from './components/ai'
import MorphList from './components/morphs'

const App = () => {
  return (
    <Layout>
      {/* <Loading/> */}
      <H h={1}>Backgrounds</H>
      <Backgrounds />
      <GearList/>
      <AiList/>
      <MorphList/>
    </Layout>
  )
}

export default App
