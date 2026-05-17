"use client";
import api from "@/utils/axios"
import { useParams, useRouter } from "next/navigation";
import { use, useEffect, useState } from "react";

export default function Cx() {

    const params = useParams();
    const [data, setData] = useState()
    const router = useRouter();

    const onLoad = async function () {
        const res = await api.post("/quiz/" + params.quiz_uuid + "/attempt")
        // debugger
        setData(res?.data);
    }
    useEffect(() => {
        onLoad();
        
    }, [])

    useEffect(()=>{
        router.push("/quiz/attempt/"+data?.quiz_attempt_uuid);
    },[data?.quiz_attempt_uuid])
    return <>Start to attempt Quiz ID : {params.quiz_uuid}</>
}