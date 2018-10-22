import React from 'react'
import { Tag } from 'carbon-components-react'

const BOOK_MAP = {
  'EP': 'ibm',
  'RW': 'third-party',
  'PAN': 'local',
  'SW': 'experimental',
  'GC': 'community',
  'TH': 'private',
}

const BookTag = ({book}) => (<Tag type={BOOK_MAP[book]}>{book}</Tag>)

export default BookTag
