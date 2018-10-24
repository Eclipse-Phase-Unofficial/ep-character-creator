import capitalize from 'lodash/fp/capitalize'
import groupBy from 'lodash/fp/groupBy'
import mapKeys from 'lodash/fp/mapKeys'
import pipe from 'lodash/fp/pipe'
import values from 'lodash/fp/values'

import React, { Fragment } from 'react'
import { connect } from 'react-redux'

import { GroupedList, NameAndBookLine } from '../../common/list'
import { withColumn } from '../../hoc/grid'

const MorphList = ({morphs}) => (
  <Fragment>
    <GroupedList
      title="Select a morph"
      values={morphs}
      renderLine={NameAndBookLine}
    />
  </Fragment>
)

const mapStateToProps = ({refData}) => ({
  morphs: pipe(values, groupBy('morphType'), mapKeys(capitalize))(refData.morphs.data)
})

export default connect(mapStateToProps)(withColumn({ xs: 2 })(MorphList))
