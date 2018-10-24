import pipe from 'lodash/fp/pipe'
import values from 'lodash/fp/values'
import groupBy from 'lodash/fp/groupBy'
import mapKeys from 'lodash/fp/mapKeys'
import capitalize from 'lodash/fp/capitalize'

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

const gearType = type => {
  switch(type) {
    case 'SOF':
      return "Kinetic Weapons"
    case 'STD':
      return "Energy Weapons"
    case 'WMG':
      return "Spray Weapons"
    case 'WEG':
      return "Seeker Weapons"
    case 'WKG':
      return "Melee Weapons"
    case 'WSG':
      return "Ammunition"
    case 'WXG':
      return "Grenades and Missiles"
    case 'WSE':
      return "Weapon Accessories"
    case 'WAM':
      return "Armor"
    case 'WAC':
      return "Drugs"
    case 'ARM':
      return "Poisons"
    case 'IMG':
      return "Chemicals"
    case 'DRG':
      return "Pets"
    case 'CHG':
      return "Vehicles"
    case 'POG':
      return "Robots"
    case 'PEG':
      return "Misc."
  }
}

const getGearType = gear => gearType(gear.gearType)
const mapStateToProps = ({refData}) => ({
  // gear: {
  //   "Software": refData.softGear.data,
  //   "Gear": refData.hardGear.data
  // },
  gear: pipe(values, groupBy(getGearType), mapKeys(capitalize))(refData.hardGear.data)

})

export default connect(mapStateToProps)(withColumn({ xs: 2 })(GearList))
