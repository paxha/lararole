import React from 'react';
import {Breadcrumb, Button, Form, Input, Layout, Tooltip} from 'antd';
import {DeploymentUnitOutlined, HomeOutlined, QuestionCircleOutlined} from '@ant-design/icons';
import {Link} from "react-router-dom";

const {Content} = Layout;

const formItemLayout = {
    labelCol: {
        xs: {
            span: 16,
        },
        sm: {
            span: 4,
        },
    },
    wrapperCol: {
        xs: {
            span: 24,
        },
        sm: {
            span: 16,
        },
    },
};

const tailFormItemLayout = {
    wrapperCol: {
        xs: {
            span: 24,
            offset: 0,
        },
        sm: {
            span: 16,
            offset: 4,
        },
    },
};

const NewModuleForm = (id) => {
    const [form] = Form.useForm();

    const onFinish = values => {
        axios.post('/lararole/api/module/' + (id.id ? id.id + '/' : '') + 'create', {
            name: values.name,
            alias: values.alias,
            icon: values.icon
        }).then((response) => {
            window.location = "/lararole/module"
        })
    };

    return (
        <Form
            {...formItemLayout}
            form={form}
            name="module"
            onFinish={onFinish}
            scrollToFirstError
        >
            <Form.Item label="Module Name" name="name" rules={[
                {required: true, message: 'Module name is required'}
            ]}>
                <Input placeholder="Product Management, Order Processing etc..."/>
            </Form.Item>

            <Form.Item
                label={
                    <span>
                        Alias&nbsp;
                        <Tooltip title="What do you want to show alternate of module name?">
                            <QuestionCircleOutlined/>
                        </Tooltip>
                    </span>
                }
                name="alias"
                rules={[
                    {
                        required: true,
                        message: 'Please input your alias!',
                    },
                ]}>
                <Input placeholder="Product Management, Order Processing etc..."/>
            </Form.Item>

            <Form.Item
                label="Icon"
                name="icon"
                rules={[
                    {
                        required: false,
                        message: 'Please input your icon name or icon path!',
                    },
                ]}>
                <Input placeholder="fa fa-users etc..."/>
            </Form.Item>

            <Form.Item {...tailFormItemLayout}>
                <Button type="primary" htmlType="submit">
                    Create Module
                </Button>
            </Form.Item>
        </Form>
    );
};

function Create(props) {
    const {id} = props.match.params;

    return (
        <div>
            <Breadcrumb style={{margin: '16px 0'}}>
                <Breadcrumb.Item>
                    <Link to="/lararole">
                        <HomeOutlined/> Home
                    </Link>
                </Breadcrumb.Item>
                <Breadcrumb.Item>
                    <Link to="/lararole/module">
                        <DeploymentUnitOutlined/> Module
                    </Link>
                </Breadcrumb.Item>
                <Breadcrumb.Item>
                    Create
                </Breadcrumb.Item>
            </Breadcrumb>
            <Content className="site-layout-background" style={{
                padding: 24,
                margin: 0,
            }}>
                <NewModuleForm id={id}/>
            </Content>
        </div>
    );
}

export default Create;
