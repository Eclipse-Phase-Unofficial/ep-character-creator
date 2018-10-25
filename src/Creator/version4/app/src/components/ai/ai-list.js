import values from 'lodash/fp/values'
import React, { Fragment } from 'react'
import { connect } from 'react-redux'

import { List, NameAndBookLine } from '../../common/list'

const AiList = ({ai}) => (
  <Fragment>
    <List
      title="Select a AI"
      values={ai}
      renderLine={NameAndBookLine}
    />
  </Fragment>
)

const mapStateToProps = ({refData}) => ({
  ai: values(refData.ai.data),
})

const enhancedAiList = connect(mapStateToProps)(AiList)
export default enhancedAiList
