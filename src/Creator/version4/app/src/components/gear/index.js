import React, { Fragment } from 'react'
import { connect } from 'react-redux'

import { GroupedList, NameAndBookLine } from '../../common/list'
import { withColumn } from '../../hoc/grid'

const GearList = ({gear}) => (
  <Fragment>
    <GroupedList
      title="Select a gear"
      values={gear}
      renderLine={NameAndBookLine}
    />
  </Fragment>
)

const mapStateToProps = ({refData}) => ({
  gear: {
    "Software": refData.softGear.data,
    "Gear": refData.hardGear.data
  }
})

export default connect(mapStateToProps)(withColumn({ xs: 2 })(GearList))
