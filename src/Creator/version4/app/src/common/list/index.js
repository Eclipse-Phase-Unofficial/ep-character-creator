import keys from 'lodash/fp/keys'
import map from 'lodash/fp/map'
import size from 'lodash/fp/size'
import kebabCase from 'lodash/fp/lowerCase'


import React  from 'react'
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
  display: flex;
  position: relative;
  height: 500px;
  padding: 0;
  
  flex-direction: column;
`

const ListHeader = styled.h6`
  flex-shrink: 0;
  height: 46px;
  background-color: white;
  border-bottom: 1px solid blue;
`

const ListWrapper = styled.div`
  flex: 1;
  overflow: scroll;
`

const listWrapper = Component => props => (<Wrapper>
  <ListHeader>{props.title}</ListHeader>
  <ListWrapper>
    <Component {...props}/>
  </ListWrapper>
</Wrapper>)


export const List = listWrapper(({title, renderLine, values}) => (
  <TileGroup key={`${kebabCase(title)}-list-body`} name={`${title}-list`}>
    {
      size(values) > 0 &&
      map(renderLine)(values)
    }
  </TileGroup>
))
List.propTypes = {
  renderLine: PropTypes.func.isRequired,
  title: PropTypes.string.isRequired,
  values: PropTypes.array,
}
List.defaultProps = {
  values: {}
}

export const GroupedList = listWrapper(({title, renderLine, values}) => (
  <Accordion>
    {
      map(key =>
        <AccordionItem open={true} key={`${key}-accordion`} title={key} className='grouped-list'
        >
          <TileGroup key={`${key}-list-body`} name={`${key}-list`}>
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

