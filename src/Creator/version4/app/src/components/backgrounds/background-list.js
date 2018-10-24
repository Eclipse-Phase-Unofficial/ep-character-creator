import values from 'lodash/fp/values'

import React, { Fragment } from 'react'
import { connect } from 'react-redux'

import { List, NameAndBookLine } from '../../common/list'

const BackgroundList = ({backgrounds}) => (
  <Fragment>
    <List
      title="Choose a background"
      values={backgrounds}
      renderLine={NameAndBookLine}
    />
  </Fragment>
)

const mapStateToProps = ({refData}) => ({
  backgrounds: values(refData.backgrounds.data)
})

export default connect(mapStateToProps)(BackgroundList)
