import React from 'react'
import { Layout } from 'antd'

const { Header } = Layout

function MyHeader () {
  return (
    <Header
      className="site-layout-background"
      style={{
        position: 'fixed',
        zIndex: 1,
        width: '100%'
      }}
    />
  )
}

export default MyHeader
