import keys from 'lodash/fp/keys'
import map from 'lodash/fp/map'
import size from 'lodash/fp/size'

import React, { Fragment } from 'react'
import PropTypes from 'prop-types'

import styled from 'styled-components'
import {
  Accordion,
  AccordionItem,
  Tile,
  RadioTile,
  TileGroup
} from 'carbon-components-react'

import ButtonTag from '../book-tag/index'

import './List.scss'

const Wrapper = styled(Tile)`
  position: relative;
  height: 500px;
  overflow: scroll;
  padding: 0;
`

const ListHeader = styled.h6`
  z-index: 1;
  position: sticky;
  height: 46px;
  top: 0;
  width: 100%;
  background-color: white;
  border-bottom: 1px solid blue;
`

const listWrapper = Component => props => (<Wrapper>
  <ListHeader>{props.title}</ListHeader>
  <Component {...props}/>
</Wrapper>)

export const List = listWrapper(({renderLine, values}) => (
  <Fragment>
    {
      size(values) > 0 &&
      map(renderLine)(values)
    }
  </Fragment>
))
List.propTypes = {
  renderLine: PropTypes.func.isRequired,
  title: PropTypes.string.isRequired,
  values: PropTypes.array,
}
List.defaultProps = {
  values: {}
}

export const GroupedList = listWrapper(({renderLine, values}) => (
  <Accordion>
    {
      map(key =>
        <AccordionItem key={`${key}_accordion`} title={key} className='grouped-list'
        >
          <TileGroup key={`${key}_list_body`} name={`${key}_list`}>
            {
              size(values[key]) > 0 &&
              map(renderLine)(values[key])
            }
          </TileGroup>
        </AccordionItem>)(keys(values))
    }
  </Accordion>
))
GroupedList.propTypes = {
  ...List.propTypes,
  values: PropTypes.object,
}
GroupedList.defaultProps = {
  values: {}
}

export const NameAndBookLine = (value) => (
  <RadioTile
    id={value.atomUid}
    key={value.atomUid}
    name={value.atomUid}
    value={value.atomUid}
  >
    {value.name}
    <ButtonTag book={value.book}/>
  </RadioTile>)

