import React from 'react'
import { Breadcrumb, Card, Col, Row, Statistic, Timeline } from 'antd'
import { ArrowDownOutlined, ArrowUpOutlined, HomeOutlined } from '@ant-design/icons'
import { Link } from 'react-router-dom'

function Home () {
  return (
    <div>
      <Breadcrumb style={{ margin: '16px 0' }}>
        <Breadcrumb.Item>
          <Link to="/lararole">
            <HomeOutlined/> Home
          </Link>
        </Breadcrumb.Item>
      </Breadcrumb>
      <Card title={'Modules'}>
        <Row gutter={16}>
          <Col span={4}>
            <Statistic title="Total Modules" value={24}/>
          </Col>
          <Col span={4}>
            <Statistic
              title="Active"
              value={24}
              valueStyle={{ color: '#3f8600' }}
              prefix={<ArrowUpOutlined/>}
            />
          </Col>
          <Col span={4}>
            <Statistic
              title="Idle"
              value={24}
              valueStyle={{ color: '#cf1322' }}
              prefix={<ArrowDownOutlined/>}
            />
          </Col>
          <Col span={4}>
            <Statistic title="Trashed" value={24} valueStyle={{ color: '#cf1322' }}/>
          </Col>
        </Row>
      </Card>
      <Card title={'Roles'} style={{ marginTop: 16 }}>
        <Row gutter={16}>
          <Col span={4}>
            <Statistic title="Total Modules" value={24}/>
          </Col>
          <Col span={4}>
            <Statistic title="Recent Added" value={24} valueStyle={{ color: '#3f8600' }}/>
          </Col>
          <Col span={4}>
            <Statistic
              title="Active"
              value={24}
              valueStyle={{ color: '#3f8600' }}
              prefix={<ArrowUpOutlined/>}
            />
          </Col>
          <Col span={4}>
            <Statistic
              title="Idle"
              value={24}
              valueStyle={{ color: '#cf1322' }}
              prefix={<ArrowDownOutlined/>}
            />
          </Col>
          <Col span={4}>
            <Statistic title="Trashed" value={24} valueStyle={{ color: '#cf1322' }}/>
          </Col>
        </Row>
      </Card>

      <Row gutter={16} style={{ marginTop: 16 }}>
        <Col span={12}>
          <Card
            title="Recent Activities"
          >
            <Timeline>
              <Timeline.Item color="green">Create a services site 2015-09-01</Timeline.Item>
              <Timeline.Item color="green">Create a services site 2015-09-01</Timeline.Item>
              <Timeline.Item color="red">
                <p>Solve initial network problems 1</p>
                <p>Solve initial network problems 2</p>
                <p>Solve initial network problems 3 2015-09-01</p>
              </Timeline.Item>
              <Timeline.Item>
                <p>Technical testing 1</p>
                <p>Technical testing 2</p>
                <p>Technical testing 3 2015-09-01</p>
              </Timeline.Item>
              <Timeline.Item color="gray">
                <p>Technical testing 1</p>
                <p>Technical testing 2</p>
                <p>Technical testing 3 2015-09-01</p>
              </Timeline.Item>
              <Timeline.Item color="gray">
                <p>Technical testing 1</p>
                <p>Technical testing 2</p>
                <p>Technical testing 3 2015-09-01</p>
              </Timeline.Item>
            </Timeline>
          </Card>
        </Col>
      </Row>
    </div>
  )
}

export default Home
