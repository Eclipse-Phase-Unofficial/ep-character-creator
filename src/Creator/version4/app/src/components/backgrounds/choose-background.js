import _ from 'lodash'
import React from 'react'
import styled from 'styled-components'
import {
  Button,
  StructuredListBody,
  StructuredListCell,
  StructuredListHead,
  StructuredListInput,
  StructuredListRow,
  StructuredListWrapper,
  Tile
} from 'carbon-components-react'

import ButtonTag from '../book-tag'
import { connect } from 'react-redux'

const BackgroundLine = ({background}) => (<StructuredListRow label htmlFor="row-1">
  <StructuredListInput
    id={background.atomUid}
    value={background.name}
    title={background.name}
    name={background.name}
    defaultChecked
  />
  <StructuredListCell>
    {background.name}
  </StructuredListCell>
  <StructuredListCell>
    <ButtonTag book={background.book}/>
  </StructuredListCell>
  <StructuredListCell>
    <Button small onClick={() => console.log('test')}>
      Select
    </Button>
  </StructuredListCell>
</StructuredListRow>)

const Wrapper = styled(Tile)`
  position: relative;
  height: 500px;
  overflow: scroll;
  padding: 0;
`

// TODO:: import colors
const ListHeader = styled.h6`
  z-index: 1;
  position: sticky;
  height: 46px;
  top: 0;
  width: 100%;
  background-color: white;
  border-bottom: 1px solid blue;
`


const ChooseBackground = ({backgrounds}) => (
  <Wrapper>
    <ListHeader>Choose a background</ListHeader>
    <StructuredListWrapper selection>
      <StructuredListBody>
        {_.map(backgrounds, (background, index) => <BackgroundLine key={index} background={background}/>)}
      </StructuredListBody>
    </StructuredListWrapper>
  </Wrapper>)

const mapStateToProps = state => ({
  backgrounds: _.values(state.data.backgrounds.data)
})

export default connect(mapStateToProps)(ChooseBackground)
