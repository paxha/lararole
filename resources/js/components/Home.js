import React, { useEffect, useState } from 'react'
import { Card, Col, Row, Statistic } from 'antd'
import { ArrowDownOutlined, ArrowUpOutlined } from '@ant-design/icons'

function Home () {
  const [totalModules, setTotalModules] = useState(0)
  const [activeModules, setActiveModules] = useState(0)
  const [idleModules, setIdleModules] = useState(0)
  const [trashedModules, setTrashedModules] = useState(0)

  const [totalRoles, setTotalRoles] = useState(0)
  const [recentRoles, setRecentRoles] = useState(0)
  const [activeRoles, setActiveRoles] = useState(0)
  const [idleRoles, setIdleRoles] = useState(0)
  const [trashedRoles, setTrashedRoles] = useState(0)

  useEffect(() => {
    axios.get('/lararole/api/module-stats')
      .then(response => {
        setTotalModules(response.data.stats.total)
        setActiveModules(response.data.stats.active)
        setIdleModules(response.data.stats.idle)
        setTrashedModules(response.data.stats.trashed)
      })
    axios.get('/lararole/api/role-stats')
      .then(response => {
        setTotalRoles(response.data.stats.total)
        setRecentRoles(response.data.stats.recent)
        setActiveRoles(response.data.stats.active)
        setIdleRoles(response.data.stats.idle)
        setTrashedRoles(response.data.stats.trashed)
      })
  }, [])

  return (
    <>
      <Card
        title={'Modules'}
        style={{ marginTop: 16 }}
      >
        <Row gutter={16}>
          <Col span={4}>
            <Statistic
              title="All Modules"
              value={totalModules}
            />
          </Col>
          <Col span={4}>
            <Statistic
              title="Active"
              value={activeModules}
              valueStyle={{ color: '#3f8600' }}
              prefix={<ArrowUpOutlined/>}
            />
          </Col>
          <Col span={4}>
            <Statistic
              title="Idle"
              value={idleModules}
              valueStyle={{ color: '#cf1322' }}
              prefix={<ArrowDownOutlined/>}
            />
          </Col>
          <Col span={4}>
            <Statistic
              title="Trashed"
              value={trashedModules}
              valueStyle={{ color: '#cf1322' }}
            />
          </Col>
        </Row>
      </Card>
      <Card
        title={'Roles'}
        style={{ marginTop: 16 }}
      >
        <Row gutter={16}>
          <Col span={4}>
            <Statistic
              title="All Roles"
              value={totalRoles}
            />
          </Col>
          <Col span={4}>
            <Statistic
              title="Recent Added"
              value={recentRoles}
              valueStyle={{ color: '#3f8600' }}
            />
          </Col>
          <Col span={4}>
            <Statistic
              title="Active"
              value={activeRoles}
              valueStyle={{ color: '#3f8600' }}
              prefix={<ArrowUpOutlined/>}
            />
          </Col>
          <Col span={4}>
            <Statistic
              title="Idle"
              value={idleRoles}
              valueStyle={{ color: '#cf1322' }}
              prefix={<ArrowDownOutlined/>}
            />
          </Col>
          <Col span={4}>
            <Statistic
              title="Trashed"
              value={trashedRoles}
              valueStyle={{ color: '#cf1322' }}
            />
          </Col>
        </Row>
      </Card>
    </>
  )
}

export default Home
