// import 'carbon-components/scss/globals/scss/styles.scss'

import React, { Component } from 'react'
import { Accordion, AccordionItem } from 'carbon-components-react'
import Layout from './components/layout'
import './App.scss'

const App = () => {
  return (
    <React.Fragment>
      <Layout>
        {/*<Loading/>*/}
        <article className="App__demo">
          <h3 className="App__demo-title">Carbon Components</h3>
          <Accordion>
            <AccordionItem title="Example">
              <p>
                This is a Component imported from Carbon and styled with the CSS
                from the main Carbon Components GitHub repo!
              </p>
            </AccordionItem>
            <AccordionItem title="Questions?">
              <p>
                Hi there!{' '}
                <span aria-label="Hand wave" role="img">
                  👋{' '}
                </span>{' '}
                if you have any questions about this demo, or are running into
                any issues setting this up in your own development environment,
                please feel free to reach out to us on Slack or make an issue on
                the GitHub Repository.
              </p>
            </AccordionItem>
          </Accordion>
        </article>
      </Layout>
    </React.Fragment>
  )
}

export default App
